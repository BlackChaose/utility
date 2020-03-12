#! /bin/bash

echo "start shutdown_huk" >> /var/log/log_huk.log
/usr/bin/curl --insecure -v "<web address to your server huk>?key=<yourkey>&mes=<pc_name>+are+shutdown" 2>&1 | tee /var/log/log_huk.log*
echo "stop shutdown_huk" >> /var/log/log_huk.log
