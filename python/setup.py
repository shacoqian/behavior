#-*- coding:utf-8 -*-
from distutils.core import setup
import py2exe

setup(options ={"py2exe": {"dll_excludes": ["MSVCP90.dll"],"compressed": 1,"optimize": 2,"bundle_files": 1}},windows=[{"script": 'behavior.py',"icon_resources": [(1,"pc.ico")]}],zipfile=None)
setup(options ={"py2exe": {"dll_excludes": ["MSVCP90.dll"],"compressed": 1,"optimize": 2,"bundle_files": 1}},windows=[{"script": 'behavior.py',"icon_resources": [(1,"pc.ico")]}],zipfile=None)           