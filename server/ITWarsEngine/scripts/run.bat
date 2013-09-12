@echo OFF
cls

REM TODO :
REM    - seul bot 1 doit �tre saisi (les autres sont tjrs java)
REM    - pouvoir choisir le nbr de joueur
REM    - sp�cialiser le run pour chaque StarterKit
REM    - les maps devraient �tre incluses dans les starters
REM    - lancer le replays apr�s (navigateur sur index.html)
REM    - d�tecter si replay_err ou replay_log contiennent des donn�es ==> si oui, les afficher


REM Exemple d'appel : run map1.txt "node MyBot.js" "php MyBot.php"

if "x%1" == "x" goto usage
if x%2 == x goto usage
if x%3 == x goto usage
set map=%1
set bot1=%2
set bot2=%3

echo Running game beetwen '%bot1%' and '%bot2%' on '%map%'
echo.

:runEngine
java -Duser.language=en -jar engine.jar %map% 1000 1000 replay_log.txt %bot1% %bot2% 1>replay.json 2>replay_err.txt
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