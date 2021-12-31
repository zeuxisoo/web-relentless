all:
	@echo "Command    : Description"
	@echo "---------- : -----------"
	@echo "generate   : Generate the dict.big.cantonese.txt"
	@echo "copy       : Copy the dict.big.cantonese.txt to storage path"
	@echo "clean      : Clean the virtualenv and generated dict text files"
	@echo

generate: clean
	python3 -m venv venv
	source venv/bin/activate && pip install -r requirements.txt
	./venv/bin/python3 gen_merged_dict.py

copy:
	cp -Rf dict.big.cantonese.txt ../storage

clean:
	rm -rf ./venv
	rm -rf dict.*.txt
