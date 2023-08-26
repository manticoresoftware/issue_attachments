import os, time, sys, random, MySQLdb, operator, sphinxapi, string
from threading import Thread
from threading import Event

##########################################################################

INDEX = 'i1'
NUM_THREADS = 4
TIME_LIMIT = 20
ql_port = 8306

DO_QUERIES = Event()


if os.name=="nt":
	mytime = time.clock
else:
	mytime = time.time


def die ( msg ):
	print msg
	sys.exit ( 1 )


class SqlClientThread ( Thread ):
	def __init__ ( self, tid ):
		Thread.__init__ ( self )
		self.tid = tid
		self.conn = MySQLdb.connect ( host="127.0.0.1", user="root", passwd="", db="", port=ql_port )
		self.cursor = self.conn.cursor ()
		self.num_queries = 0
		self.failed_queries = 0
		self.text = ''
		for i in range(100):
			self.text = self.text + ' ' + ''.join(random.choice(string.ascii_uppercase + string.digits) for _ in range(10))

	def run ( self ):
		while ( not DO_QUERIES.isSet() ):
			pass

		while DO_QUERIES.isSet():
			q = ''
			try:
				self.num_queries += 1
				id = random.randint ( 1, 1000000 )
				q = "REPLACE INTO rt2 ( id, idd, title ) VALUES ( %d, %d, 'test yes %s')" % ( id, id, self.text )
				self.cursor.execute ( q )
				rows = self.cursor.fetchall()
			except Exception, e:
				self.failed_queries += 1
				print ( '%d %s, query:\n%s\n' % ( self.tid, e, q ) )

class ReadClientThread ( Thread ):
	def __init__ ( self, tid ):
		Thread.__init__ ( self )
		self.tid = tid
		self.conn = MySQLdb.connect ( host="127.0.0.1", user="root", passwd="", db="", port=ql_port )
		self.cursor = self.conn.cursor ()
		self.dt = 0
		self.num_queries = 1

	def run ( self ):
		while ( not DO_QUERIES.isSet() ):
			pass

		while DO_QUERIES.isSet():
			q = "show index rt2 status"
			try:
				start = mytime()
				self.cursor.execute ( q )
				rows = self.cursor.fetchall()
				self.dt += ( mytime() - start )
				self.num_queries += 1
				time.sleep ( 0.05 )
			except Exception, e:
				self.failed_queries += 1
				print ( '%d %s, query:\n%s\n' % ( self.tid, e, q ) )

##########################################################################


i = 1
while (i<len(sys.argv)):
	arg = sys.argv[i]
	i += 1
	if arg=='-i':
		INDEX = sys.argv[i]
		i += 1
	elif arg=='-n':
		NUM_THREADS = int(sys.argv[i])
		i += 1
	elif arg=='-t':
		TIME_LIMIT = int(sys.argv[i])
		i += 1

thds = [ SqlClientThread ( tid ) for tid in range(NUM_THREADS) ]
[ t.start() for t in thds ]
read_thd = ReadClientThread ( 100 )
read_thd.start()

start = mytime()
elapsed = 0
q_ok = 0
q_fail = 0
DO_QUERIES.set()
while DO_QUERIES.isSet() and ( TIME_LIMIT==0 or elapsed < TIME_LIMIT ):
	time.sleep ( 0.01 )
	q_ok = 0
	q_fail = 0
	for t in thds:
		q_ok = q_ok + t.num_queries
		q_fail = q_fail + t.failed_queries

	dt = read_thd.dt / read_thd.num_queries
	elapsed = mytime() - start
	sys.stdout.write ( "elapsed %d, dt %.3f, %d(%d) \r" % ( elapsed, dt, q_ok, q_fail ) )
DO_QUERIES.clear()
elapsed = mytime()-start

[ t.join(0.01) for t in thds ]
read_thd.join(0.01)

tm_total = mytime()-start

elapsed = max ( elapsed, 0.01 )
tm_total = max ( tm_total, 0.01 )

total_queries = 0
total_failed = 0
for t in thds:
	total_queries += t.num_queries
	total_failed += t.failed_queries

print '%d threads, %d queries (%d failed), %.3f (%.3f) sec, %.1f ( %.3f ) qps' % ( len ( thds ), total_queries, total_failed, elapsed, tm_total, total_queries/elapsed, total_queries/tm_total )
