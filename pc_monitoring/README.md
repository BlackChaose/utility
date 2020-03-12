---
### pc_monitoring simple scripts ###

1) create service for start & shutdown pc
	
	* create 2 file: ```notice-boot.service``` & ```notice-shutdown.service``` in directory ```/etc/systemd/system```
	
	
2) enable service

	* run command ```systemctl enable notice-boot```
    * run command ```systemctl start notice-boot```
    * run command ```systemctl enable notice-shutdown```
    * run command ```systemctl start notice-shutdown```

3) create channel of send data

	* ToDo: in process

4) todo: add server's part 