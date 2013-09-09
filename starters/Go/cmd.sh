# example de commandes utiles pour linux
export GOPATH=${PWD}
go install myBot
go test myBot
go test myBot -bench . -benchtime 10s -cpuprofile prof.out -memprofile mem.out -benchmem
go tool pprof ./myBot.test src/myBot/prof.out
