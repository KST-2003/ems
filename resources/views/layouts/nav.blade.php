<nav class="header-nav ms-auto">
       <ul class="d-flex align-items-center">
           <li class="nav-item dropdown pe-3">
               <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                   <span class="d-none d-md-block dropdown-toggle ps-2">Menu</span>
               </a>
               <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                   <li class="dropdown-header">
                       <h6>@if(Auth::check()){{ Auth::user()->name }}@endif</h6>
                       <span>Admin</span>
                   </li>
                   <li>
                       <hr class="dropdown-divider">
                   </li>
                   <li>
                       <a class="dropdown-item d-flex align-items-center" href="">
                           <i class="bi bi-person"></i>
                           <span>My Profile</span>
                       </a>
                   </li>
                   <li>
                       <hr class="dropdown-divider">
                   </li>
                   <li>
                       <a class="dropdown-item d-flex align-items-center" href="">
                           <i class="bi bi-gear"></i>
                           <span>Account Settings</span>
                       </a>
                   </li>
                   <li>
                       <hr class="dropdown-divider">
                   </li>
                   <li>
                       <a class="dropdown-item d-flex align-items-center" style="cursor:pointer" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                           <i class="bi bi-box-arrow-right"></i>
                           <span>Sign Out</span>
                           <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                               @csrf
                           </form>
                       </a>
                   </li>
               </ul>
           </li>
       </ul>
   </nav>