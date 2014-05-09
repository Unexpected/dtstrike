CXXFLAGS=-std=c++0x -Wall -Wextra -Werror
LDFLAGS=

SOURCES=${wildcard *.cpp}
HEADERS=${wildcard *.h}
OBJECTS=$(SOURCES:%.cpp=%.o)

NAME=cpp_starter_kit
BIN=MyBot
ZIP=${NAME}.zip

all: ${BIN}

zip: ${ZIP}

test:${BIN}
	bash -c 'cd test; . run.sh'

test_all:${BIN}
	bash -c 'cd test; . test_all.sh'

${BIN}: ${OBJECTS}
	${CXX} -o $@ $^ ${LDFLAGS}


# zip depends on the binary to ensure that it compiles, at least.
${ZIP}: ${BIN}
	rm -f ${ZIP}
	zip ${ZIP} *.cpp *.h

%.o:%.cpp ${HEADERS}
	${CXX} -o $@ -c $< ${CXXFLAGS}

.PHONY: clean all test test_all zip

clean:
	rm -rf *.o ${BIN} ${ZIP}
