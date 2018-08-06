#!/bin/sh
echo `date`
mongo --eval `echo test_index.js`
echo `date`
