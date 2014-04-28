@echo OFF
cls
setlocal
echo.

REM TODO :
REM    - specialiser le run pour chaque StarterKit


REM Load config
for /F "tokens=1,* delims==" %%A in (run.conf) do (
	if "%%A"=="map" set map=%%B
	if "%%A"=="bot_cmd" set bot_cmd=%%B
)

REM Check config
if "x%map%" == "xrandom" (
	call :random_map
)
if "x%map%" == "x" (
	set error=Map file not specified
	goto config_error
)
if not exist %map% (
	set error=Map file not found
	goto config_error
)
if "x%bot_cmd%" == "x" (
	set error=Invalid command bot
	goto config_error
)
REM Extract nb players from map
for /F "tokens=2 delims=\" %%a in ('echo %map%') do (
	set nb_players=%%a
)
if "x%nb_players%" == "x" (
	set error=Player number not specified
	goto config_error
)
echo %nb_players%| findstr /r "^[2-6]$">nul
if errorlevel 1 (
	set error=Invalid player number
	goto config_error
)

REM Define other bots -- you may replace these commands with your own bots
set bot1="java -jar bots/CrazyBot.jar"
set bot2="java -jar bots/DedicatedBot.jar"
set bot3="java -jar bots/DefensiveBot.jar"
set bot4="java -jar bots/DispersionBot.jar"
set bot5="java -jar bots/FlightyBot.jar"
set bot6="java -jar bots/LooterRageBot.jar"
set bot7="java -jar bots/PatientBot.jar"
set bot8="java -jar bots/WarriorRageBot.jar"

echo Starting game with %nb_players% players on map %map%
echo.
echo In the replay file, your bot is always player 1
echo.

:runEngine
set err_file=replay_err.txt
if %nb_players% == 2 set run_cmd="%bot_cmd%" %bot1%
if %nb_players% == 3 set run_cmd="%bot_cmd%" %bot1% %bot2%
if %nb_players% == 4 set run_cmd="%bot_cmd%" %bot1% %bot2% %bot3%
if %nb_players% == 5 set run_cmd="%bot_cmd%" %bot1% %bot2% %bot3% %bot4%
if %nb_players% == 6 set run_cmd="%bot_cmd%" %bot1% %bot2% %bot3% %bot4% %bot5%

REM Cleaning previous game
del %err_file% 2>NUL
del replay_log.txt 2>NUL
del visu\replay.js 2>NUL

echo var replayJson=>visu\replay.js
java -server -Xms128m -Xmx512m -Duser.language=en -jar engine.jar %map% 1000 1000 replay_log.txt %run_cmd% 1>>visu\replay.js 2>%err_file%
set errorcode=%errorlevel%
if %errorcode% LEQ 0 goto error
set winner=%errorcode%

REM Check if replay_err is empty
for %%A in (%err_file%) do if not %%~zA==0 goto error

REM Launch replay
start visu/index.html
echo.
echo ====================================================================
echo ====================================================================
if "x%winner%" == "x1" (
	echo                Game over - you WIN
) else (
	echo                Game over - you LOSE
)
echo.
echo         If it has not done so, you may watch the replay
echo          by opening visu/index.html in your browser.
echo ====================================================================
echo ====================================================================
echo.
pause
goto end

:random_map
REM Random player number (beetween 2 and 5)
set /a nb_players=%RANDOM% * (6 - 2 + 1) / 32768 + 2
REM Random map number
if %nb_players% == 2 set /a map_num=%RANDOM% * ( 33 -   1 + 1) / 32768 +   1
if %nb_players% == 3 set /a map_num=%RANDOM% * ( 66 -  34 + 1) / 32768 +  34
if %nb_players% == 4 set /a map_num=%RANDOM% * ( 99 -  67 + 1) / 32768 +  67
if %nb_players% == 5 set /a map_num=%RANDOM% * (132 - 100 + 1) / 32768 + 100
if %nb_players% == 6 set /a map_num=%RANDOM% * (165 - 133 + 1) / 32768 + 133
set map=maps\%nb_players%\map%map_num%.txt
goto:eof

:error
echo !!!!!!!!!!!!!!!!! Game crashed !!!!!!!!!!!!!!!!!
echo.
type %err_file%
echo.
echo !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
echo.
pause
goto end

:config_error
echo !!!!!!!!!!!!!!!!! Error !!!!!!!!!!!!!!!!!!
echo.
echo Invalid configuration :
echo     %error%
echo.
echo Check the configuration file 'run.conf'
echo.
echo !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
echo.
pause

:end