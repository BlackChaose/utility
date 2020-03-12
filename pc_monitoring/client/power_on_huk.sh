#! /bin/bash

echo "start power_on_huk" >> /var/log/log_huk.log
/usr/bin/curl --insecure -v "<web address to your server huk>?key=<your key>&mes=<pc_name>+are+power+on" 2>&1 | tee /var/log/log_huk.log*
echo "stop power_on_huk" >> /var/log/log_huk.log
