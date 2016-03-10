def main():
    color = int(input("Enter a HEX color (without the #): "), 16)
    print("Your color: #%s" % hex(color))
    
    #Find the 3 0-255 color channels from the hex color
    red = color >> 16 & 0xFF
    green = color >> 8 & 0xFF
    blue = color & 0xFF
    
    print("Red: %d, Green: %d, Blue: %d" % (red, green, blue))
    
    amountToAdd = int(input("Enter a number to add to each channel: "))
    
    #Add to the colors
    red = clamp(red + amountToAdd, 0, 255)
    green = clamp(green + amountToAdd, 0, 255)
    blue = clamp(blue + amountToAdd, 0, 255)
    
    print("Red: %d, Green: %d, Blue: %d" % (red, green, blue))
    
    color = 0
    color = color | blue
    color = color | (green << 8)
    color = color | (red << 16)
    
    print("New color: #%s" % hex(color))


def clamp(number, minNum, maxNum):
    return max(minNum, min(number, maxNum))

main()