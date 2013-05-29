create database contest;
create user contest; 
set password for contest = PASSWORD('contest');
grant all privileges on contest.* to contest@'%';
grant all privileges on contest.* to contest@'localhost';
