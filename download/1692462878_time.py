#!/usr/bin/env python
# -*- coding: utf-8 -*-

__author__ = 'Alexey N. Vinogradov'

import sys, json, re, getopt, urllib, urllib2

sectoken = None
project = "dev"
group="manticoresearch"
apibase="https://gitlab.com/api/v4/projects"

def issueurl(issue):
    global apibase, group, project
    return "%s/%s%%2F%s/issues/%s"%(apibase,group,project,issue)


def read_token():
    global sectoken
    if sectoken is None:
        f = open('token', 'r')
        s = f.read()
        f.close()
        m = re.split("\"", s)
        m = re.split(":\s?",m[1])
        sectoken = m[1]

def printusage():
    print("Usage: time <spend|estimate|reset_spent|reset_estimate> <issue> [time]")
    sys.exit(0)

class postRequest (urllib2.Request):
    def get_method(self):
        return "POST"

def main():
    global project, group, sectoken, apibase

    try:
        (opts, rest) = getopt.getopt(sys.argv[1:],'p:g:',['project=', 'group='])
    except:
        printusage()

    for (opt, val) in opts:
        if opt in ['--project','-p']:
            project = val
        elif  opt in ['--group', '-g']:
            group = val

    if not rest[1:]:
        printusage()

    cmd = rest[0]
    issue = rest[1]
    duration = None
    if cmd in ['spend', 'estimate']:
        if not rest[2:]:
            printusage()
        else:
            duration = rest[2]
    elif not cmd in ['reset_spent', 'reset_estimate']:
        printusage()

    read_token()

    issueaddr = issueurl(issue)

    if cmd == 'spend':
        issueaddr = issueurl(issue) + "/add_spent_time"
    elif cmd == 'estimate':
        issueaddr = issueurl(issue) + "/time_estimate"
    elif cmd == 'reset_spent':
        issueaddr = issueurl(issue) + "/reset_spent_time"
    elif cmd == 'reset_estimate':
        issueaddr = issueurl(issue) + "/reset_time_estimate"

    data = None
    if duration is not None:
        data = urllib.urlencode({'duration': duration})

    req = postRequest (issueaddr, data)

    req.add_header("PRIVATE-TOKEN", sectoken)
    f = urllib2.urlopen(req)
    jsonresult = json.loads(f.read())
    f.close()

    print "Totally spent %d minutes (%s) to issue %s in %s."%(jsonresult['total_time_spent'],jsonresult['human_total_time_spent'],issue, project)


if __name__ == '__main__':
    main()