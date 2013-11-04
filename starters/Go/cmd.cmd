rem example de commandes utiles pour windows
SET GOPATH=C:\Users\lansardk\workspace\dtstrike\starters\Go
go install main
go test main
go test myBot -bench . -benchtime 10s -cpuprofile prof.out -memprofile mem.out -benchmem

go tool pprof ./myBot.test src/myBot/prof.out
rem on win64... you need to use with a 64 bit perl and the pprof_win.pl provided :
perl pprof_win.pl ./myBot.test src/myBot/prof.out
