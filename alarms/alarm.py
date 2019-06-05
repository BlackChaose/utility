#!/usr/bin/env python
#-*- coding: UTF-8 -*-

import gtk

def button_clicked(button):
    print 'Hello World!'

def main():
    window = gtk.Window()
    window.set_default_size(240, 180)
    window.set_title('Hello World!')
    window.connect('destroy', lambda w: gtk.main_quit())

    button = gtk.Button('Press Me')
    button.connect('clicked', button_clicked)
    button.show()

    window.add(button)
    window.present()

    gtk.main()

if __name__ == '__main__':
    main()