CFLAGS=-Wall -Wextra -Werror -O3
LDFLAGS=-g -lm

SOURCES=${wildcard *.c}
HEADERS=${wildcard *.h}
OBJECTS=$(SOURCES:%.c=%.o)

NAME=c_starter_kit
BIN=MyBot
ZIP=${NAME}.zip

all: ${BIN}

zip: ${ZIP}

test:${BIN}
	bash -c 'cd test; . run.sh'

test_all:${BIN}
	bash -c 'cd test; . test_all.sh'

${BIN}: ${OBJECTS}
	${CC} -o $@ $^ ${LDFLAGS}


# zip depends on the binary to ensure that it compiles, at least.
${ZIP}: ${BIN}
	rm -f ${ZIP}
	zip ${ZIP} *.c *.h

%.o:%.c ${HEADERS}
	${CC} -o $@ -c $< ${CFLAGS}

.PHONY: clean all test test_all zip

clean:
	rm -rf *.o ${BIN} ${ZIP}
