import { Component, OnInit } from '@angular/core';
import { Session } from 'src/app/core/models/session';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-user-settings-profile',
  templateUrl: './user-settings-profile.component.html',
  styleUrls: ['./user-settings-profile.component.css'],
})
export class UserSettingsProfileComponent implements OnInit {
  constructor(
    private _sessionService: SessionService,
    private _userService: UserService
  ) { }

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  id: number;
  name: string;
  surname1: string;
  name_2: string;
  surname1_2: string;
  surname2: string;
  email: string;
  username: string;
  password: string;
  isArtist: number;
  imgAvatar: FileList;
  aboutMe: string;

  ngOnInit(): void {
    this.id = this.user.id;
    this.name = this.user.name;
    this.surname1 = this.user.surname1;
    this.name_2 = this.user.name;
    this.surname1_2 = this.user.surname1;
    this.surname2 = this.user.surname2;
    this.email = this.user.email;
    this.username = this.user.username;
    this.password = this.user.password;
    this.isArtist = this.user.isArtist;
    this.imgAvatar = this.user.imgAvatar;
    this.aboutMe = this.user.aboutMe;
  }

  imgToUpload: FileList = null;
  showingImgHint: boolean = false;
  changeImgAvatar(newImgAvatar: FileList) {
    if (newImgAvatar != null) {
      let allowed = ['image/png', 'image/jpg', 'image/jpeg'];
      if (!allowed.includes(newImgAvatar.item(0).type)) this.showingImgHint = true;
      else this.showingImgHint = false;
      this.imgToUpload = newImgAvatar;
    }
  }

  userEdited: User;
  edit() {
    this._userService.edit(this.id, this.name, this.surname1, this.surname2, this.email, this.username, this.aboutMe, this.imgToUpload).subscribe(
      (result) => {
        this.userEdited = result.user;
        let userSession = new Session(result.token, this.userEdited);
        this._sessionService.setCurrentSession(userSession);
        location.reload();
      }, (error) => {
        console.log(error);
    });
  }

  showingUsernameHint: boolean = false;
  showUsernameHint() {
    if (!this.showingUsernameHint) this.showingUsernameHint = true;
    else this.showingUsernameHint = false;
  }

  showingNameHint: boolean = false;
  showNameHint() {
    if (!this.showingNameHint) this.showingNameHint = true;
    else this.showingNameHint = false;
  }

  showingSurname1Hint: boolean = false;
  showSurname1Hint() {
    if (!this.showingSurname1Hint) this.showingSurname1Hint = true;
    else this.showingSurname1Hint = false;
  }

  showingSurname2Hint: boolean = false;
  showSurname2Hint() {
    if (!this.showingSurname2Hint) this.showingSurname2Hint = true;
    else this.showingSurname2Hint = false;
  }

  message:string = '';
  existingUser: boolean = false;
  existUserByEmail() {
    this._userService.existUserByEmail(this.id, this.email).subscribe(
      (result) => {
        this.existingUser = false;
      }, (error) => {
        if (error != null) {
          this.existingUser = true;
          this.message = error.error;
        }
      }
    )
  }

  existUserByUsername() {
    this._userService.existUserByUsername(this.id, this.username).subscribe(
      (result) => {
        this.existingUser = false;
      }, (error) => {
        if (error != null) {
          this.existingUser = true;
          this.message = error.error;
        }
      }
    )
  }

  timer;
  editingAnimation() {
    clearInterval(this.timer);
    Swal.fire({
      title: '??Est??s seguro de que quieres guardar los cambios?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({ title: 'Modificando perfil...', showConfirmButton: false, timerProgressBar: true, timer: 1000,
          didOpen: () => { Swal.showLoading(); },
        }).then((result) => {
          if (result.dismiss === Swal.DismissReason.timer) {
            Swal.fire({
              title: 'Perfil modificado',
              position: 'center',
              icon: 'success',
              showConfirmButton: false,
              timer: 1000,
            }).then((result) => {
              this.edit();
            });
          }
        });
      }
    });
  }

}
