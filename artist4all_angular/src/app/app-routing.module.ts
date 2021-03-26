import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { RegisterComponent } from './components/public/register/register.component';
import { LoginComponent } from './components/public/login/login.component';
import { AppComponent } from './app.component';
import { HomeComponent } from './components/secure/home/home.component';
import { LandingComponent } from './components/public/landing/landing.component';
import { StoreComponent } from './components/secure/store/store.component';
import { ProfileComponent } from './components/secure/profile/profile.component';
import { MessagesComponent } from './components/secure/messages/messages.component';
import { ContactComponent } from './components/public/contact/contact.component';
import { AuthGuard } from './core/guards/auth.guard';
import { PageNotFoundComponent } from './components/public/page-not-found/page-not-found.component';
import { CreatePublicationComponent } from './components/secure/publications/create-publication/create-publication.component';
import { ProvisionalAddUserComponent } from './components/secure/provisional-add-user/provisional-add-user.component';
import { UserSettingsAccountComponent } from './components/secure/settings/user-settings-account/user-settings-account.component';
import { UserSettingsProfileComponent } from './components/secure/settings/user-settings-profile/user-settings-profile.component';
import { UserSettingsPasswordComponent } from './components/secure/settings/user-settings-password/user-settings-password.component';

const routes: Routes = [
  {path:'', component:LandingComponent},
  {path:'register',component:RegisterComponent},
  {path:'login', component:LoginComponent},
  {path:'home', component:HomeComponent, canActivate:[AuthGuard]},
  {path:'store',component:StoreComponent, canActivate:[AuthGuard]},
  {path:'profile/:username',component:ProfileComponent, canActivate:[AuthGuard]},
  {path:'settings/profile',component:UserSettingsProfileComponent, canActivate:[AuthGuard]},
  {path:'settings/account',component:UserSettingsAccountComponent, canActivate:[AuthGuard]},
  {path:'settings/password',component:UserSettingsPasswordComponent, canActivate:[AuthGuard]},
  {path:'message',component:MessagesComponent, canActivate:[AuthGuard]},
  {path:'contact', component:ContactComponent},
  {path:'publication',component:CreatePublicationComponent, canActivate:[AuthGuard]},
  {path:'user', component:ProvisionalAddUserComponent, canActivate:[AuthGuard]},
  {path:'404', component:PageNotFoundComponent},
  {path: '**', redirectTo: '/404'},
];

@NgModule({
  imports: [RouterModule.forRoot(routes, { useHash: true })],
  //imports: [RouterModule.forRoot(routes, { relativeLinkResolution: 'legacy' })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
