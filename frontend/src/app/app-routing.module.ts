import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';
import { ProfilComponent } from './components/profil/profil.component';
import { DashboardComponent } from './components/dashboard/dashboard.component';
import { AuthGuard } from './services/auth.guard';
import { IsAdminGuard } from './services/is-admin.guard';
import { ErrorPageComponent } from './components/error-page/error-page.component';
import { ReverseAuthGuard } from './services/reverse-auth.guard';

const routes: Routes = [
  {path: 'login', component: LoginComponent, canActivate:[ReverseAuthGuard]},
  {path: 'user/register', component: RegisterComponent, canActivate:[IsAdminGuard]},
  {path: 'user/profil/:id', component: ProfilComponent, canActivate:[AuthGuard]},
  {path: 'dashboard', component: DashboardComponent, canActivate:[AuthGuard]},
  {path: '', redirectTo: '/login', pathMatch:'full'},
  {path: '**', component: ErrorPageComponent},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
