@echo OFF
cls
Setlocal EnableDelayedExpansion
echo.

REM Global parameters
set err_file=test_replay_err.txt
set log_file=test_replay_log.txt
set rep_file=test_replay.js

REM Load config
for /F "tokens=1,* delims==" %%A in (run.conf) do (
	if "%%A"=="bot_cmd" set bot_cmd=%%B
)

REM Check config
if "x%bot_cmd%" == "x" (
	echo !!!!!!!!!!!!!!!!! Error !!!!!!!!!!!!!!!!!!
	echo.
	echo Invalid configuration :
	echo     Invalid command bot
	echo.
	echo Check the configuration file 'run.conf'
	echo.
	echo !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	echo.
	pause
	goto :eof
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

echo ====================================================================
echo This will test your bot on every available map %%
echo ====================================================================

set TOT=0
set NBWIN=0
set NBLOST=0
set LIMIT=0
for /f %%p in ('dir /b maps\') do (
	for /f %%m in ('dir /b maps\%%p\') do (
		REM Launch a match
		call :launchgame %%p %%m
		if %LIMIT% GTR 0 (
			if %LIMIT% LEQ !TOT! goto noerror
		)
	)
	echo.
)
goto noerror

:error
echo !!!!!!!!!!!!!!!!! Game crashed !!!!!!!!!!!!!!!!!
echo.
type %err_file%
echo.
echo !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
echo.

:noerror
REM Cleaning last game
del %err_file% 2>NUL
del %log_file% 2>NUL
del %rep_file% 2>NUL

REM Display result
set /a PWIN=(%NBWIN%*100)/%TOT%
set /a PLOST=100-%PWIN%
echo.
echo ====================================================================
echo ====================================================================
echo Number total of games : %TOT%
echo        Number of wins : %NBWIN% (%PWIN% %%)
echo        Number of lost : %NBLOST% (%PLOST% %%)
echo ====================================================================
echo ====================================================================
pause
goto end


:launchgame
set /a TOT+=1
set nb_players=%1
set map=maps\%1\%2

echo Starting game #!TOT! with %nb_players% players on map %map%

if "x%nb_players%" == "x2" set run_cmd="%bot_cmd%" %bot1%
if "x%nb_players%" == "x3" set run_cmd="%bot_cmd%" %bot1% %bot2%
if "x%nb_players%" == "x4" set run_cmd="%bot_cmd%" %bot1% %bot2% %bot3%
if "x%nb_players%" == "x5" set run_cmd="%bot_cmd%" %bot1% %bot2% %bot3% %bot4%
if "x%nb_players%" == "x6" set run_cmd="%bot_cmd%" %bot1% %bot2% %bot3% %bot4% %bot5%

REM Cleaning previous game
del %err_file% 2>NUL
del %log_file% 2>NUL
del %rep_file% 2>NUL

java -server -Xms128m -Xmx512m -Duser.language=en -jar engine.jar %map% 1000 1000 %log_file% %run_cmd% 1>%rep_file% 2>%err_file%
set errorcode=%errorlevel%
if "%errorcode%" LEQ "0" goto error
set winner=%errorcode%

if "x%winner%" == "x1" (
	set /a NBWIN+=1
) else (
	set /a NBLOST+=1
)
echo   WIN : !NBWIN! / LOST : !NBLOST! - TOTAL : !TOT!
goto :eof

:end