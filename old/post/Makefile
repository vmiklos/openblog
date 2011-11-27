CFLAGS ?= -Wall -march=i686 -O2 -pipe
CFLAGS += $(shell mysql_config --cflags)
LDFLAGS = $(shell mysql_config --libs)

insert: util.o insert.o

clean:
	rm -rf insert *.o

test:
	cat foo.eml|./insert
