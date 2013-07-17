@echo OFF
cls

if "x%1" == "x" goto usage
if x%2 == x goto usage
if x%3 == x goto usage
set map=%1
set bot1=%2
set bot2=%3

echo Running game beetwen '%bot1%' and '%bot2%' on '%map%'
echo.

:runEngine
java -jar engine.jar %map% 1000 1000 log.txt %bot1% %bot2% 1>game.txt 2>game_err.txt
if not "%errorlevel%" == "0" goto error
echo Game ended
goto end

:error
echo !!!!!!!!!!!!!!!!! Game crashed !!!!!!!!!!!!!!!!!
goto end

:usage
echo Wrong usage
echo Usage : run.bat MapName "command for MyFirstBot" "command for MySecondBot"

:end
pause