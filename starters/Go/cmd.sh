export GOPATH=${PWD}
go install main
go test launcher
go test launcher -bench . -benchtime 100s -cpuprofile prof.out -memprofile mem.out -benchmem
go tool pprof ./launcher.test src/launcher/prof.out
