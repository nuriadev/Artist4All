import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { User } from 'src/app/model/user';
import { UserService } from 'src/app/services/user.service';

@Component({
  selector: 'app-user-navbar',
  templateUrl: './user-navbar.component.html',
  styleUrls: ['./user-navbar.component.css'],
  providers: [UserService]
})
export class UserNavbarComponent implements OnInit {

  constructor(private _userService: UserService, private _router: Router) { }

  id:number;
  name:string = "";
  surname1:string = "";
  surname2:string = "";
  email:string = "";
  username:string = "";
  img:string = "";


  ngOnInit(): void {
    this._userService.isAuthenticated(localStorage.getItem("token")).subscribe(
      (result) => {
        if(result['respuesta'] == 'ok') {
          this._userService.getUserData(localStorage.getItem("token")).subscribe(
            (result) => {
              this.id = result["id_user"];
              this.name = result["name_user"];
              this.surname1 = result["surname1"];
              this.surname2 = result["surname2"];
              this.email = result["email"];
              this.username = result["username"];
              this.img = result["img"];
              console.log(result);
            },
            (error) => {
              console.log(error);
            }
          )
        } else {
          this._router.navigate(['']);
        }
      },
      (error) => {
        console.log(error);
      }
    )
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
