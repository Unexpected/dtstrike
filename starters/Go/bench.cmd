call env.cmd
go test launcher -bench . -benchtime 100s -cpuprofile prof.out -memprofile mem.out -benchmem