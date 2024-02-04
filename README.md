
# ShortLinks 🔥
A very simple PHP & SQLite based URL shortener, written on a rainy Sunday afternoon within 2hrs.<br>
Please feel free to use and extend it 😆 

### 🔸Usage
Add a Link
> domain.tld/index.php?mode=add

Retrieve a Link
> domain.tld/index.php?mode=fetch&uid=aBcD0<br>
or if using mod_rewrite<br>
domain.tld/link/aBcD0


### 🔸SQL Scheme
```
CREATE TABLE IF NOT EXISTS "url" (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	uid TEXT,
	url TEXT,
	created NUMERIC,
	accessed NUMERIC,
	views INTEGER NOT NULL DEFAULT 0
);
```

### 🔸.htaccess
```
RewriteEngine ON
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^link/([^/]*)$ /index.php?mode=fetch&uid=$1 [QSA,NC,L]
```

### 🔸License
Copyright ©️ 2024 Dave Beusing

MIT License - https://opensource.org/license/mit/

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the “Software”), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished 
to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT 
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION 
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.