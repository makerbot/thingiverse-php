##Thingiverse
A PHP wrapper for the Thingiverse API.

##Use
###Configuration
1. Login at http://www.thingiverse.com/developers
2. Click on your app, then 'Edit this app'
3. In the `__construct()` method, enter your Client ID and Client Secret into their designated variables
4. Also enter your URL for Thingiverse to forward the OAuth code to in redirect_uri

###Implementation
1. Import thingiverse.php `require_once 'thingiverse.php'`
2. Create a new instance `$thingiverse = new Thingiverse();`
3. Use `$thingiverse->makeLoginURL()` to create a link to authorize a user account for your app
4. Once you receive a code from Thingiverse, `$thingiverse->oauth('code here');`
5. You're all set! 

##License
The MIT License (MIT)

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.