import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';
import { RouterModule} from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatRadioModule } from '@angular/material/radio';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';
import { UserServiceService } from './services/user-service.service';
import { ProfilComponent } from './components/profil/profil.component';
import { DashboardComponent } from './components/dashboard/dashboard.component';
import { NavbarComponent } from './components/navbar/navbar.component';
import { ForgetPasswordComponent } from './components/forget-password/forget-password.component';
import { MailSentComponent } from './components/mail-sent/mail-sent.component';
import { ResetPasswordComponent } from './components/reset-password/reset-password.component';
import { SidebarComponent } from './components/sidebar/sidebar.component';
import { AddComponent } from './components/Attestations/add/add.component';
import { EditComponent } from './components/Attestations/edit/edit.component';
import { AttestationHomePageComponent } from './components/Attestations/attestation-home-page/attestation-home-page.component';


@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    RegisterComponent,
    ProfilComponent,
    DashboardComponent,
    NavbarComponent,
    ForgetPasswordComponent,
    MailSentComponent,
    ResetPasswordComponent,
    SidebarComponent,
    AddComponent,
    EditComponent,
    AttestationHomePageComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    RouterModule,
    FormsModule,
    ReactiveFormsModule,
    MatRadioModule,
  ],
  providers: [ UserServiceService ],
  bootstrap: [AppComponent]
})
export class AppModule { }
