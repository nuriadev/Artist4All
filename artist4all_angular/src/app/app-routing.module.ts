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

const routes: Routes = [
  {path:'', component:LandingComponent},
  {path:'register',component:RegisterComponent},
  {path:'login', component:LoginComponent},
  {path:'home', component:HomeComponent},
  {path:'store',component:StoreComponent},
  {path:'profile',component:ProfileComponent},
  {path:'settings',component:UserSettingsComponent},
];

@NgModule({
  imports: [RouterModule.forRoot(routes, { relativeLinkResolution: 'legacy' })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
