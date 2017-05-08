<!-- Branding / Navigation -->
<nav class="row splash-nav">
    <div class="col-md-10 col-md-offset-1">
        <div class="pull-left splash-brand">
            <i class="fa fa-btn fa-sun-o"></i>Laravel Spark
        </div>

        <div class="navbar-header">
            <button type="button" class="splash-nav-toggle navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#primary-nav" aria-expanded="false" aria-controls="primary-nav">
                <span class="sr-only">Toggle navigation</span>
                MENU
            </button>
        </div>

        <div id="primary-nav" class="navbar-collapse collapse splash-nav-list">
            <ul class="nav navbar-nav navbar-right inline-list">
                <li class="splash-nav-link active"><a href="/features">Features</a></li>
                <li class="splash-nav-link"><a href="/support">Support</a></li>
                @if(Auth::guest())
                    <li class="splash-nav-link splash-nav-link-highlight"><a href="/login">Login</a></li>
                    <li class="splash-nav-link splash-nav-link-highlight-border"><a href="/register">Register</a></li>
                @else
                    <li class="splash-nav-link splash-nav-link-highlight-border"><a href="/home">Dashboard</a></li>
                @endif
            </ul>
        </div>

        <div class="clearfix"></div>
    </div>
</nav>