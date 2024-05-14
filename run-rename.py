#!/usr/bin/python3

import sys
import os
import re
import shutil

if len(sys.argv) < 2:
    newName = input('Please enter new extra namespace, like "super-extra": ')
else:
    newName = sys.argv[1]

if not newName:
    print("Could not determine the new extra name")
    exit(1)

parts = list(filter(None, re.split('[^a-zA-Z]', newName)))
if len(parts) < 2:
    print('Namespace should include at least one delimiter')
    exit(1)
parts = list(map(lambda x: x.lower(), parts))

replacement = {
    'mmx-app': '-'.join(parts),
    'mmx_app': '_'.join(parts),
    'mmx/app': parts[0] + '/' + '-'.join(parts[1:]),
    'mmxApp': parts[0] + ''.join(list(map(lambda x: x.title(), parts[1:]))),
    'MMX\\App':
        parts[0].upper() + '\\' + '\\'.join(list(map(lambda x: x.title(), parts[1:])))
        if parts[0] == 'mmx' else '\\'.join(list(map(lambda x: x.title(), parts)))
}
replacement['MMX\\\\App'] = replacement['MMX\\App'].replace('\\', '\\\\')


def process_path(path):
    if os.path.isfile(path):
        process_file(path)
    else:
        for root, dirs, files in os.walk(path):
            for file in files:
                process_file(root + '/' + file)


def process_file(file):
    with open(file, 'r+') as fread:
        text = fread.read()
        for key, value in replacement.items():
            text = text.replace(key, value)

        with open(file, 'w+') as fwrite:
            fwrite.write(text)
            print('Processed file ' + file)

    if 'mmx-app' in file:
        renamed = file.replace('mmx-app', replacement['mmx-app'])
        os.rename(file, renamed)
        print('Moved ' + file + ' to ' + renamed)


directories = [
    'assets/src',
    'assets/vite.config.ts',
    'bin',
    'core/db',
    'core/elements',
    'core/lexicon',
    'core/src',
    'core/bootstrap.php',
    'core/phinx.php',
    'composer.json',
]
files = [
    'docker-compose.yml',
    '.env',
    'run-install.sh',
]

if not os.path.isfile('.env'):
    os.rename('.env.dist', '.env')

if os.path.isdir('mmx-app'):
    os.rename('mmx-app', replacement['mmx-app'])
    for directory in directories:
        process_path(replacement['mmx-app'] + '/' + directory)

for file in files:
    if os.path.isfile(file):
        process_file(file)

print('Deleting unnecessary files...')

if os.path.isdir('.git'):
    try:
        shutil.rmtree('.git')
    except OSError as e:
        pass

os.remove(__file__)

print('Success!')