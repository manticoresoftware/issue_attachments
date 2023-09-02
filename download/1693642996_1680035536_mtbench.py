import os, time, sys, operator, traceback, random 
import requests, json
from threading import Thread
from threading import Event

##########################################################################

CLUSTER = 'jobsite'
NUM_THREADS = 8
TIME_LIMIT = 30
HOST = 'http://161.35.31.11:9312/json/bulk'

DO_QUERIES = Event()


if os.name=="nt":
	mytime = time.clock
else:
	mytime = time.time


def die ( msg ):
	print ( msg )
	sys.exit ( 1 )


class HTTPClientThread ( Thread ):
	def __init__ ( self, tid, queries ):
		Thread.__init__ ( self )
		self.tid = tid

		self.num_queries = 0
		self.failed_queries = 0
		self.queries = queries
		self.qlen = len ( queries )
		self.doc_id_base = self.tid * 100000 + random.randint(1,1000000)

	def get_line ( self, src_json ):
		doc_id = src_json["insert"]["id"]
		doc_id = doc_id + self.doc_id_base + self.num_queries
		src_json["insert"]["id"] = doc_id
		return json.dumps(src_json)
		
	def run ( self ):
		while ( not DO_QUERIES.isSet() ):
			pass

		while DO_QUERIES.isSet():
			try:
				self.num_queries += 1
				
				headers = {"Content-Type": "application/x-ndjson"}
				
				body = ''.join([ self.get_line ( item ) for item in self.queries ])
				
				r = requests.post ( HOST, headers=headers, data=body, timeout=25.0 )
				if r.status_code != requests.codes.ok:
					self.failed_queries += 1
					print ( "%d status %d, %s" % ( self.tid, r.status_code, r.content ) )
			except Exception as e:
				self.failed_queries += 1
				print ( "error at %d '%s'" % ( self.tid, e ) )
				#print(traceback.format_exc())

def fix_cluster ( line ):
	json_src = json.loads ( line )
	json_src["insert"]["cluster"] = CLUSTER
	return json_src


##########################################################################

fn = '2_qr_no_esc.txt'

i = 1
while (i<len(sys.argv)):
	arg = sys.argv[i]
	i += 1
	if arg=='-c':
		CLUSTER = sys.argv[i]
		i += 1
	elif arg=='-n':
		NUM_THREADS = int(sys.argv[i])
		i += 1
	elif arg=='-t':
		TIME_LIMIT = int(sys.argv[i])
		i += 1
	elif arg=='-h':
		HOST = sys.argv[i]
		i += 1
	elif arg=='-p':
		PORT = int(sys.argv[i])
		i += 1
	else:
		fn = arg

fp = open(fn, 'r')
if not fp:
	die ( "failed to open %s" % fn )
queries = fp.readlines()
fp.close()

queries = [fix_cluster(line) for line in queries]

thds = []
for tid in range(NUM_THREADS):
    thds.append ( HTTPClientThread ( tid, queries ) )
[ t.start() for t in thds ]

start = mytime()
elapsed = 0
DO_QUERIES.set()
while DO_QUERIES.isSet() and ( TIME_LIMIT==0 or elapsed < TIME_LIMIT ):
	time.sleep ( 0.01 )
	
	total_queries = 0
	total_failed = 0
	for t in thds:
		total_queries += t.num_queries
		total_failed += t.failed_queries
	
	elapsed = mytime() - start
	sys.stdout.write ( "elapsed %d %d(%d) \r" % ( elapsed, total_queries, total_failed ) )
DO_QUERIES.clear()
elapsed = mytime()-start

[ t.join(0.1) for t in thds ]

tm_total = mytime()-start

elapsed = max ( elapsed, 0.01 )
tm_total = max ( tm_total, 0.01 )

total_queries = 0
total_failed = 0
for t in thds:
	total_queries += t.num_queries
	total_failed += t.failed_queries

print ( '%d threads, %d queries (%d failed), %.3f (%.3f) sec, %.1f ( %.3f ) qps' % ( len ( thds ), total_queries, total_failed, elapsed, tm_total, total_queries/elapsed, total_queries/tm_total ) )
