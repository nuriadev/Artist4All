import { Component, OnInit } from '@angular/core';
import { AuthenticationService } from 'src/app/core/services/authentication.service';
import { SessionService } from 'src/app/core/services/session.service';

@Component({
  selector: 'app-user-navbar',
  templateUrl: './user-navbar.component.html',
  styleUrls: ['./user-navbar.component.css'],
  providers: [AuthenticationService]
})
export class UserNavbarComponent implements OnInit {

  constructor(
    private _authenticationService: AuthenticationService,
    private _sessionService: SessionService
  ) { }

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  name:string;
  surname1:string;
  surname2:string;
  email:string;
  username:string;
  password:string;
  imgAvatar:FileList;

  ngOnInit(): void {
    this.name = this.user.name;
    this.surname1 = this.user.surname1;
    this.surname2 = this.user.surname2;
    this.email = this.user.email;
    this.username = this.user.username;
    this.password = this.user.password;
    this.imgAvatar = this.user.imgAvatar;
  }

  logout() {
    this._authenticationService.logout(this.token).subscribe(
      (response) => {
        this._sessionService.logout();
      }, (error) => {
        console.log(error);
      }
    )
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
