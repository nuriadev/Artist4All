import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'Artist4All';

  logueado = false;

  isAuthenticated() {
    if(localStorage.getItem("token") && localStorage.getItem("currentUser")) this.logueado = true;
    else this.logueado = false;
    return this.logueado;
  }

  logout() {
    localStorage.removeItem("token");
    localStorage.removeItem("currentUser");
    localStorage.clear();
    this.logueado = false;
  }

}
