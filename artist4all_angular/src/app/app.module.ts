import { BrowserModule } from '@angular/platform-browser';
import { NgModule, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';


import { NgxSpinnerModule } from "ngx-spinner";
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatBadgeModule } from '@angular/material/badge';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { RegisterComponent } from './components/public/register/register.component';
import { LoginComponent } from './components/public/login/login.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { HomeComponent } from './components/secure/home/home.component';
import { LandingComponent } from './components/public/landing/landing.component';
import { ProfileComponent } from './components/secure/profile/index-profile/index-profile.component';
import { StoreComponent } from './components/secure/store/store.component';
import { UserNavbarComponent } from './components/secure/user-navbar/user-navbar.component';
import { MessagesComponent } from './components/secure/messages/messages.component';
import { ContactComponent } from './components/public/contact/contact.component';
import { AuthGuard } from './core/guards/auth.guard';
import { UserService } from './core/services/user.service';
import { UserSidebarComponent } from './components/secure/user-sidebar/user-sidebar.component';
import { SessionService } from './core/services/session.service';
import { AuthenticationService } from './core/services/authentication.service';
import { FooterComponent } from './components/public/footer/footer.component';
import { PageNotFoundComponent } from './components/public/page-not-found/page-not-found.component';
import { CreatePublicationComponent } from './components/secure/publications/create-publication/create-publication.component';
import { ViewPublicationComponent } from './components/secure/publications/view-publication/view-publication.component';
import { EditPublicationComponent } from './components/secure/publications/edit-publication/edit-publication.component';
import { ProvisionalAddUserComponent } from './components/secure/provisional-add-user/provisional-add-user.component';
import { UserSettingsProfileComponent } from './components/secure/settings/user-settings-profile/user-settings-profile.component';
import { UserSettingsAccountComponent } from './components/secure/settings/user-settings-account/user-settings-account.component';
import { UserSettingsPasswordComponent } from './components/secure/settings/user-settings-password/user-settings-password.component';
import { ListOfFollowersOrFollowedComponent } from './components/secure/profile/list-of-followers-or-followed/list-of-followers-or-followed.component';

@NgModule({
  declarations: [
    AppComponent,
    RegisterComponent,
    LoginComponent,
    HomeComponent,
    LandingComponent,
    ProfileComponent,
    StoreComponent,
    UserNavbarComponent,
    MessagesComponent,
    ContactComponent,
    UserSidebarComponent,
    FooterComponent,
    PageNotFoundComponent,
    CreatePublicationComponent,
    ViewPublicationComponent,
    EditPublicationComponent,
    ProvisionalAddUserComponent,
    UserSettingsProfileComponent,
    UserSettingsAccountComponent,
    UserSettingsPasswordComponent,
    ListOfFollowersOrFollowedComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule,
    HttpClientModule,
    CommonModule,
    NgxSpinnerModule,
    BrowserAnimationsModule,
    ReactiveFormsModule,
    MatButtonModule,
    MatIconModule,
    MatBadgeModule
  ],
  providers: [UserService, AuthenticationService, SessionService, AuthGuard],
  bootstrap: [AppComponent],
  schemas: [CUSTOM_ELEMENTS_SCHEMA]
})
export class AppModule { }
