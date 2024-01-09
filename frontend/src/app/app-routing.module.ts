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
import { ForgetPasswordComponent } from './components/forget-password/forget-password.component';
import { MailSentComponent } from './components/mail-sent/mail-sent.component';
import { ResetPasswordComponent } from './components/reset-password/reset-password.component';
import { AttestationHomePageComponent } from './components/Attestations/attestation-home-page/attestation-home-page.component';

const routes: Routes = [
  {path: 'login', component: LoginComponent, canActivate:[ReverseAuthGuard]},
  {path: 'forget-password', component: ForgetPasswordComponent},
  {path: 'forget-password/mail-sent', component: MailSentComponent},
  {path: 'reset-password', component: ResetPasswordComponent, data: {token: ''}},
  {path: 'user/register', component: RegisterComponent, canActivate:[IsAdminGuard]},
  {path: 'user/profil/:id', component: ProfilComponent, canActivate:[AuthGuard]},
  {path: 'dashboard', component: DashboardComponent, canActivate:[AuthGuard]},
  {path: 'attestations', component: AttestationHomePageComponent, canActivate:[AuthGuard]},
  // ***********error and default pages
  {path: '', redirectTo: '/dashboard', pathMatch:'full'},
  {path: '**', component: ErrorPageComponent},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
