@echo OFF

set DIR=%~dp0

echo Building engine
cd %DIR%/server/ITWarsEngine/ant
call ant -f engine.xml
echo .. OK
echo.

echo Building sample bots
cd %DIR%/sample_bots
call ant
echo .. OK
echo.

REM echo Building LocalTester
REM cd %DIR%/LocalTester/ant
REM ant
REM echo ".. OK"
REM echo

echo Building and Coping starter kits
cd %DIR%/starters
call ant copy_to_site
echo .. OK
echo.

cd %DIR%/
