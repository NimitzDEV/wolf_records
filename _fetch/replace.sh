#!bin/bash
for file in ${@}
do
  echo ${file}
  ed ${file} << EOF
h
1
i
INSERT INTO users (vid,persona,player,role,dtid,end,sklid,tmid,life,rltid) VALUES
.
$
s/),/);/g
w
q
EOF
done
