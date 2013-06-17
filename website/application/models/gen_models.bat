@echo OFF
cls

set PATH=D:\dev\php\xampp\mysql\bin;%PATH%
set mysql=mysql -u dtstrike -pdtstrike -h localhost dtstrike -B -N

set query=%mysql% -e "select distinct `TABLE_NAME` from INFORMATION_SCHEMA.COLUMNS where `TABLE_SCHEMA` = 'dtstrike' and `TABLE_NAME`<>'ci_sessions' order by `TABLE_NAME`"
REM set query=%mysql% -e "select distinct `TABLE_NAME` from INFORMATION_SCHEMA.COLUMNS where `TABLE_SCHEMA` = 'dtstrike' and `TABLE_NAME`<>'ci_sessions' order by `TABLE_NAME` limit 2"

echo Start
for /f %%i in ('%query%') do call :genModel %%i
goto end

:genModel
echo   Generate model for %1
set tableName=%1
set subtableName=%tableName:~0,1%
call :UpCase subtableName
set modelName=%subtableName%%tableName:~1%model
set fileName=%modelName%.php

REM Header
echo ^<?php > %fileName%
echo Class %modelName% extends Model { >> %fileName%
echo. >> %fileName%

echo   var $TABLE_NAME = '%tableName%'; >> %fileName%
echo. >> %fileName%

REM Recuperation des variables
set subquery=%mysql% -e "select `COLUMN_NAME`, `COLUMN_TYPE`, `DATA_TYPE` from INFORMATION_SCHEMA.COLUMNS where `TABLE_NAME` = '%1' order by `ORDINAL_POSITION`"
for /f "delims=" %%a in ('%subquery%') do (
	call :genVar %%a
)

REM Constructor
echo. >> %fileName%
echo   function %modelName%() {>> %fileName%
echo     // Call the Model constructor>> %fileName%
echo     parent::Model();>> %fileName%
echo   }>> %fileName%

REM Functions
echo. >> %fileName%
echo   function getAll() {>> %fileName%
echo     $query = $this-^>db-^>query($this-^>TABLE_NAME);>> %fileName%
echo     if ($query-^>num_rows()) > 0) {>> %fileName%
echo       return $query-^>result();>> %fileName%
echo     }>> %fileName%
echo   }>> %fileName%

REM Footer
echo. >> %fileName%
echo } >> %fileName%
echo. >> %fileName%

goto:eof

:genVar
echo     Var %1 of type %2
set defVal=''
if "x%3" =="xint" set defVal=-1
echo   var $%1 = %defVal%; // %2>> %fileName%
goto:eof


:UpCase
:: Subroutine to convert a variable VALUE to all UPPER CASE.
:: The argument for this subroutine is the variable NAME.
FOR %%i IN ("a=A" "b=B" "c=C" "d=D" "e=E" "f=F" "g=G" "h=H" "i=I" "j=J" "k=K" "l=L" "m=M" "n=N" "o=O" "p=P" "q=Q" "r=R" "s=S" "t=T" "u=U" "v=V" "w=W" "x=X" "y=Y" "z=Z") DO CALL SET "%1=%%%1:%%~i%%"
goto:eof

:end
echo End
pause