import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-user-navbar',
  templateUrl: './user-navbar.component.html',
  styleUrls: ['./user-navbar.component.css']
})
export class UserNavbarComponent implements OnInit {

  constructor() { }

  ngOnInit(): void {
  }

  logueado = false;

  isAuthenticated() {
    if(localStorage.getItem("token")) this.logueado = true;
    else this.logueado = false;
    return this.logueado;
  }

  logout() {
    localStorage.removeItem("token");
    localStorage.clear();
    this.logueado = false;
  }

  isDisplayed = false;
  isMenuDisplayed() {
    if (!this.isDisplayed) {
      document.getElementById("menu-toggle").style.display = "block";
      this.isDisplayed = true;
    } else {
      document.getElementById("menu-toggle").style.display = "none";
      this.isDisplayed = false;
    }
  }

}
