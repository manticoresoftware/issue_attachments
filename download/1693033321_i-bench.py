import os, time, sys, random, MySQLdb, operator, sphinxapi
from threading import Thread
from threading import Event

##########################################################################

INDEX = 'i1'
NUM_THREADS = 4
TIME_LIMIT = 20

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
		self.conn = MySQLdb.connect ( host="127.0.0.1", user="root", passwd="", db="", port=8306 )
		self.cursor = self.conn.cursor ()
		self.num_queries = 0
		self.failed_queries = 0

	def run ( self ):
		while ( not DO_QUERIES.isSet() ):
			pass

		while DO_QUERIES.isSet():
			q = ''
			try:
				self.num_queries += 1
				q = "REPLACE INTO rt_save ( id, idd, title ) VALUES ( %d, %d, 'test yes%d')" % ( self.num_queries, self.num_queries + 1000, self.num_queries )
				self.cursor.execute ( q )
				rows = self.cursor.fetchall()
			except Exception, e:
				self.failed_queries += 1
				print ( '%d %s, query:\n%s\n' % ( self.tid, e, q ) )


##########################################################################

fn = 'j-id.txt'

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
	else:
		fn = arg

thds = [ SqlClientThread ( tid ) for tid in range(NUM_THREADS) ]
[ t.start() for t in thds ]

start = mytime()
elapsed = 0
DO_QUERIES.set()
while DO_QUERIES.isSet() and ( TIME_LIMIT==0 or elapsed < TIME_LIMIT ):
	time.sleep ( 0.01 )
	elapsed = mytime() - start
	sys.stdout.write ( "elapsed %d \r" % elapsed )
DO_QUERIES.clear()
elapsed = mytime()-start

[ t.join(0.01) for t in thds ]

tm_total = mytime()-start

elapsed = max ( elapsed, 0.01 )
tm_total = max ( tm_total, 0.01 )

total_queries = 0
total_failed = 0
for t in thds:
	total_queries += t.num_queries
	total_failed += t.failed_queries

print '%d threads, %d queries (%d failed), %.3f (%.3f) sec, %.1f ( %.3f ) qps' % ( len ( thds ), total_queries, total_failed, elapsed, tm_total, total_queries/elapsed, total_queries/tm_total )
