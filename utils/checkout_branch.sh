#!/bin/bash

#Using alternate branch for tests

git clone git@gitlab-new.cyberplat.com:CyberFT/qa.git app/src/tests/

cd ./app/src/tests/
 if [[ `git ls-remote origin "$CI_COMMIT_REF_NAME"` ]]  
	then 	
		git checkout "$CI_COMMIT_REF_NAME" 	
		echo "Using branch $CI_COMMIT_REF_NAME for tests." 
 fi
