export class User {
  id: number;
  name: string = '';
  surname1: string = '';
  surname2: string = '';
  email: string = '';
  username: string = '';
  password: string = '';
  isArtist: number;
  imgAvatar: FileList;
  aboutMe: string = '';
  isPrivate: number;

  constructor(
    id: number,
    name: string,
    surname1: string,
    surname2: string,
    email: string,
    username: string,
    password: string,
    isArtist: number,
    imgAvatar: FileList,
    aboutMe: string,
    isPrivate: number
  ) {
    this.id = id;
    this.name = name;
    this.surname1 = surname1;
    this.surname2 = surname2;
    this.email = email;
    this.username = username;
    this.password = password;
    this.isArtist = isArtist;
    this.imgAvatar = imgAvatar;
    this.aboutMe = aboutMe;
    this.isPrivate = isPrivate;
  }
}
