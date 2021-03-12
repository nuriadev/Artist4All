import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { RegisterComponent } from './components/public/register/register.component';
import { LoginComponent } from './components/public/login/login.component';
import { AppComponent } from './app.component';
import { HomeComponent } from './components/secure/home/home.component';
import { LandingComponent } from './components/public/landing/landing.component';
import { StoreComponent } from './components/secure/store/store.component';
import { ProfileComponent } from './components/secure/profile/profile.component';
import { UserSettingsComponent } from './components/secure/user-settings/user-settings.component';
import { MessagesComponent } from './components/secure/messages/messages.component';
import { ContactComponent } from './components/public/contact/contact.component';
import { AuthGuard } from './core/guards/auth.guard';
import { EditUserComponent } from './components/secure/edit-user/edit-user.component';

const routes: Routes = [
  {path:'', component:LandingComponent},
  {path:'register',component:RegisterComponent},
  {path:'login', component:LoginComponent},
  {path:'home', component:HomeComponent, canActivate:[AuthGuard]},
  {path:'store',component:StoreComponent, canActivate:[AuthGuard]},
  {path:'profile',component:ProfileComponent, canActivate:[AuthGuard]},
  {path:'settings',component:UserSettingsComponent, canActivate:[AuthGuard]},
  {path:'messages',component:MessagesComponent, canActivate:[AuthGuard]},
  {path:'contact', component:ContactComponent},
  {path:'profile/edit',component:EditUserComponent, canActivate:[AuthGuard]},
];

@NgModule({
  imports: [RouterModule.forRoot(routes, { relativeLinkResolution: 'legacy' })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
