<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#spark-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="/">
                {{ config('app.name') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="spark-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li><a href="/">{{ t('Home') }}</a></li>
                <li><a href="/data">{{ t('Data') }}</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Login / Registration Links -->
                <li><a href="/login">{{ t('Login') }}</a></li>
                <li><a href="/register">{{ t('Register') }}</a></li>
            </ul>
        </div>
    </div>
</nav>
