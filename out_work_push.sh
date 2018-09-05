#!/bin/bash
PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin
export PATH
curl http://kaoqin.fszx.alone.bigdatacd.com/admin/index/out_work_push
echo "----------------------------------------------------------------------------"
endDate=`date +"%Y-%m-%d %H:%M:%S"`
echo "â[$endDate] Successful"
echo "----------------------------------------------------------------------------"
