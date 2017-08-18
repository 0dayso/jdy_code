        <!-- start: Main Menu -->
        <div id="sidebar-left" class="span2 leftpanel">
            <div class="nav-collapse sidebar-nav">
                <ul class="nav nav-pills nav-stacked nav-bracket">
                    @inject('permissionPresenter','App\Presenters\PermissionPresenter')

                    {!! $permissionPresenter->menus() !!}

                </ul>
            </div>
        </div>
        <!-- end: Main Menu -->