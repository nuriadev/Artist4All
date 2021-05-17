// Modules
import { AppRoutingModule } from './app-routing.module';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { BrowserModule } from '@angular/platform-browser';
import { PickerModule } from '@ctrl/ngx-emoji-mart';
import { NgModule, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { NgxSpinnerModule } from 'ngx-spinner';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatBadgeModule } from '@angular/material/badge';
import { MatSnackBarModule } from '@angular/material/snack-bar';
import { MatInputModule } from '@angular/material/input';
import { MatTableModule } from '@angular/material/table';
import { MatExpansionModule } from '@angular/material/expansion';
import { MatDividerModule } from '@angular/material/divider';
import { MatCardModule } from '@angular/material/card';
import { MatPaginatorModule } from '@angular/material/paginator';

// Interceptors
import { AuthInterceptor } from './core/interceptors/auth.interceptor';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';

// Guards
import { AuthGuard } from './core/guards/auth.guard';

// Services
import { UserService } from './core/services/user.service';
import { SessionService } from './core/services/session.service';
import { AuthenticationService } from './core/services/authentication.service';

// Components
import { AppComponent } from './app.component';
import { RegisterComponent } from './components/public/register/register.component';
import { LoginComponent } from './components/public/login/login.component';
import { HomeComponent } from './components/secure/home/home.component';
import { LandingComponent } from './components/public/landing/landing.component';
import { ProfileComponent } from './components/secure/profile/index-profile/index-profile.component';
import { StoreComponent } from './components/secure/store/store.component';
import { UserNavbarComponent } from './components/secure/user-navbar/user-navbar.component';
import { MessagesComponent } from './components/secure/messages/messages.component';
import { EditPublicationComponent } from './components/secure/publications/edit-publication/edit-publication.component';
import { ViewPublicationWithCommentsComponent } from './components/secure/publications/view-publication-with-comments/view-publication-with-comments.component';
import { UserSettingsProfileComponent } from './components/secure/settings/user-settings-profile/user-settings-profile.component';
import { UserSettingsAccountComponent } from './components/secure/settings/user-settings-account/user-settings-account.component';
import { UserSettingsPasswordComponent } from './components/secure/settings/user-settings-password/user-settings-password.component';
import { ListOfFollowersOrFollowedComponent } from './components/secure/profile/list-of-followers-or-followed/list-of-followers-or-followed.component';
import { NotificationsPageComponent } from './components/secure/notifications-page/notifications-page.component';
import { PrivacyComponent } from './components/public/privacy/privacy.component';
import { ContactComponent } from './components/public/contact/contact.component';
import { FooterComponent } from './components/public/footer/footer.component';
import { PageNotFoundComponent } from './components/public/page-not-found/page-not-found.component';

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
    FooterComponent,
    PageNotFoundComponent,
    EditPublicationComponent,
    UserSettingsProfileComponent,
    UserSettingsAccountComponent,
    UserSettingsPasswordComponent,
    ListOfFollowersOrFollowedComponent,
    NotificationsPageComponent,
    PrivacyComponent,
    ViewPublicationWithCommentsComponent
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
    MatBadgeModule,
    MatSnackBarModule,
    MatInputModule,
    MatTableModule,
    MatExpansionModule,
    MatDividerModule,
    MatCardModule,
    MatPaginatorModule,
    PickerModule
  ],
  providers: [
    UserService,
    AuthenticationService,
    SessionService,
    AuthGuard,
    {
      provide: HTTP_INTERCEPTORS,
      useClass: AuthInterceptor,
      multi: true
    }
  ],
  bootstrap: [AppComponent],
  schemas: [CUSTOM_ELEMENTS_SCHEMA],
})
export class AppModule {}
