@echo OFF
cls
setlocal
echo.

REM TODO :
REM    - specialiser le run pour chaque StarterKit
REM    - Corriger l'ouverture du navigateur sur le replay


REM Exemple d'appel : run map1.txt "node MyBot.js" "php MyBot.php"


REM Load config
for /F "tokens=1,* delims==" %%A in (run.conf) do (
	if "%%A"=="map" set map=%%B
	if "%%A"=="bot_cmd" set bot_cmd=%%B
)

REM Check config
if "x%map%" == "x" (
	set error=Fichier de map non specifie
	goto config_error
)
if not exist %map% (
	set error=Fichier de map inexistant
	goto config_error
)
if "x%bot_cmd%" == "x" (
	set error=Commande de lancement du bot non specifie
	goto config_error
)
REM Extract nb players from map
for /F "tokens=2 delims=\" %%a in ('echo %map%') do (
	set nb_players=%%a
)
if "x%nb_players%" == "x" (
	set error=Nombre de joueurs non specifie
	goto config_error
)
echo %nb_players%| findstr /r "^[2-4]$">nul
if errorlevel 1 (
	set error=Nombre de joueurs invalide
	goto config_error
)

REM Define other bots
set bot1="java -cp engine.jar six.challenge.bot.BullyBot"
set bot2="java -cp engine.jar six.challenge.bot.LooterBot"
set bot3="java -cp engine.jar six.challenge.bot.RageBot"
set bot4="java -cp engine.jar six.challenge.bot.RandomBot"

echo Lancement de la partie a %nb_players% joueurs sur la carte %map%
echo.
echo Dans le replay, le player 1 est toujours votre bot
echo.

:runEngine
set err_file=replay_err.txt
if %nb_players% == 2 set run_cmd="%bot_cmd%" %bot1%
if %nb_players% == 3 set run_cmd="%bot_cmd%" %bot1% %bot2%
if %nb_players% == 4 set run_cmd="%bot_cmd%" %bot1% %bot2% %bot3%

REM Nettoyage partie precedente
del %err_file% 2>NUL
del replay_log.txt 2>NUL
del visu\replay.js 2>NUL

echo var replayJson=>visu\replay.js
java -Duser.language=en -jar engine.jar %map% 1000 1000 replay_log.txt %run_cmd% 1>>visu\replay.js 2>%err_file%
set winner=%errorlevel%

REM Check if replay_err is empty
for %%A in (%err_file%) do if not %%~zA==0 goto error

echo.
echo ====================================================================
echo ====================================================================
if %winner% == 1 echo                 VICTOIRE
if not %winner% == 1 echo                 defaite
echo ====================================================================
echo ====================================================================
echo.
echo (appuyez sur une touche pour lancer la lecture du replay)
pause

REM Launch replay
start visu/index.html
goto end

:error
echo !!!!!!!!!!!!!!!!! Game crashed !!!!!!!!!!!!!!!!!
echo.
type %err_file%
echo.
echo !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
echo.
pause
goto end

:usage
echo Wrong usage
echo Usage : run.bat MapName "command for MyFirstBot" "command for MySecondBot"
pause
goto end

:config_error
echo !!!!!!!!!!!!!!!!! Erreur !!!!!!!!!!!!!!!!!
echo.
echo Config invalide :
echo     %error%
echo.
echo Verifiez le fichier 'run.conf'
echo.
echo !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
echo.
pause

:end