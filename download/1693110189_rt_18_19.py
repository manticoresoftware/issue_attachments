#!/usr/bin/python

import sys, os, struct
from array import array


def die ( msg ):
	print msg
	sys.exit ( 1 )

def main():

	if len(sys.argv)<2:
		die ( 'provide a path to a META part of RT index' )

	ram_name = sys.argv[1]
	
	name, extension = os.path.splitext ( ram_name )
	if extension!='.meta':
		die ( 'provide a path to a META part of RT index, got: %s' % ram_name )

	ram_size = os.stat(ram_name).st_size
	if ram_size<8:
		die ( 'invalid size %d of %s' % ( ram_size, ram_name ) )

	ram = array('B')

	with open (ram_name,'rb') as f:
		ram.fromfile(f, ram_size)
		
	ver = struct.unpack_from('<L', ram, 4)[0]
	if ver==19:
		die ( '%s has already version %d' % ( ram_name, ver ) )
	if ver!=18:
		die ( '%s has version %d, support conversion only from ver.18 to ver.19' % ( ram_name, ver ) )
		
	struct.pack_into ( '<L', ram, 4, 19)

	old_name = ram_name + '.old'
	if os.path.isfile ( old_name ):
		os.remove ( old_name )
	os.rename ( ram_name, old_name )

	with open (ram_name,'wb') as f:
		ram.tofile(f)
	
	print ( 'converted %s ver.18 to ver.19\nkeep original META as %s' % ( ram_name, old_name ) )

if __name__ == '__main__':
	main()
