import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {

  constructor() { }

  ngOnInit(): void {
  }

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
