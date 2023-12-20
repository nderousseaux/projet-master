#!/usr/bin/env python

from coapthon.resources.resource import Resource

class Basic(Resource):
    def __init__(self, file, name="basic", coap_server=None):
        super(Basic, self).__init__(name, coap_server, visible=True, observable=True, allow_children=True)
        self.payload = "GET bien reçu"
        self.file = file

    def render_GET(self, request):
        return self

    def render_POST(self, request):
        if request.payload is not None:
            with open(self.file, '+a') as ffile:
                ffile.write(request.payload)
            self.payload = request.payload 
        return self

