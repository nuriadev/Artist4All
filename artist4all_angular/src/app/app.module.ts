import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { RegisterComponent } from './components/public/register/register.component';
import { LoginComponent } from './components/public/login/login.component';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { HomeComponent } from './components/secure/home/home.component';
import { LandingComponent } from './components/public/landing/landing.component';
import { ProfileComponent } from './components/secure/profile/profile.component';
import { StoreComponent } from './components/secure/store/store.component';
import { UserSettingsComponent } from './components/secure/user-settings/user-settings.component';
import { UserNavbarComponent } from './components/secure/user-navbar/user-navbar.component';
import { MessagesComponent } from './components/secure/messages/messages.component';
import { ContactComponent } from './components/public/contact/contact.component';
import { AuthGuard } from './core/guards/auth.guard';
import { UserService } from './core/services/user.service';
import { EditUserComponent } from './components/secure/edit-user/edit-user.component';
import { UserSidebarComponent } from './components/secure/user-sidebar/user-sidebar.component';
import { SessionService } from './core/services/session.service';
import { AuthenticationService } from './core/services/authentication.service';

@NgModule({
  declarations: [
    AppComponent,
    RegisterComponent,
    LoginComponent,
    HomeComponent,
    LandingComponent,
    ProfileComponent,
    StoreComponent,
    UserSettingsComponent,
    UserNavbarComponent,
    MessagesComponent,
    ContactComponent,
    EditUserComponent,
    UserSidebarComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule,
    HttpClientModule,
    CommonModule
  ],
  providers: [UserService, AuthenticationService, SessionService, AuthGuard],
  bootstrap: [AppComponent]
})
export class AppModule { }
