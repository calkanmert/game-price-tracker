#!/bin/bash

key=$(openssl rand -base64 32)
result="base64:$key"

echo $result
