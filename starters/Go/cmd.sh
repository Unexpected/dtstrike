# example de commandes utiles pour linux
export GOPATH=${PWD}
go install main
go test main
go test main -bench . -benchtime 10s -cpuprofile prof.out -memprofile mem.out -benchmem
go tool pprof ./myBot.test src/myBot/prof.out
