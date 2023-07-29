import os, time, sys, random, MySQLdb, operator
from threading import Thread
from threading import Event
##########################################################################
INDEX = 'test1'
NUM_THREADS = 16
TIME_LIMIT = 0
DO_QUERIES = Event()
if os.name=="nt":
    mytime = time.clock
else:
    mytime = time.time
def die ( msg ):
    print msg
    sys.exit ( 1 )
class SqlClientThread ( Thread ):
    def __init__ ( self, tid, queries ):
        Thread.__init__ ( self )
        self.tid = tid
        self.conn = MySQLdb.connect ( host="127.0.0.1", user="root", passwd="", db="", port=9306 )
        self.cursor = self.conn.cursor ()
        self.num_queries = 0
        self.failed_queries = 0
        self.queries = queries
        self.qlen = len ( queries )
    def run ( self ):
        while ( not DO_QUERIES.isSet() ):
            pass
        while DO_QUERIES.isSet():
            try:
                self.num_queries += 1
                q = self.num_queries % self.qlen
                self.cursor.execute ( "%s" % ( self.queries[q] ) )
                rows = self.cursor.fetchall()
                time.sleep ( 0.01 )
            except Exception, e:
                self.failed_queries += 1
                print ( '%d %s' % ( self.tid, e ) )
##########################################################################
fn = 'sta-q.sql'
useapi = False
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
    elif arg=='--api':
        useapi = True
    else:
        fn = arg
fp = open(fn, 'r')
if not fp:
    die ( "failed to open %s" % fn )
queries = fp.readlines()
fp.close()
thds = [ SqlClientThread ( tid, queries [ tid*len(queries)/NUM_THREADS : (tid+1)*len(queries)/NUM_THREADS ] ) for tid in range(NUM_THREADS) ]
[ t.start() for t in thds ]
start = mytime()
elapsed = 0
DO_QUERIES.set()
while DO_QUERIES.isSet() and ( TIME_LIMIT==0 or elapsed < TIME_LIMIT ):
    total_queries = 0
    total_failed = 0
    for t in thds:
        total_queries += t.num_queries
        total_failed += t.failed_queries
    time.sleep ( 0.01 )
    elapsed = mytime() - start
    sys.stdout.write ( "elapsed %d passed %d failed %d, qps %.3f \r" % ( elapsed, total_queries, total_failed, total_queries / elapsed ) )
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
Ausblenden
